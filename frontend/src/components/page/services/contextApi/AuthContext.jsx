import { createContext, useState, useEffect } from "react";
import PropTypes from "prop-types";
import { useNavigate } from "react-router-dom";
import { useCallback } from "react";
import { useQueryClient } from "@tanstack/react-query";
import jwtDecode from "jwt-decode";

export const AuthContext = createContext();

const AuthProvider = ({ children }) => {
  const [userToken, setUserToken] = useState(null);
  const [authLoading, setAuthLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [user, setUser] = useState(null);
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  

  useEffect(() => {
    const token = localStorage.getItem("token");
    if (token) {
      setIsAuthenticated(true);
      setUserToken(token);
      //raha efa misy authenticated dia ampidirina ny jwt decodé
      const decode = jwtDecode(token);
      setUser(decode);
    }
    setAuthLoading(false);
  }, []);

  const login = useCallback((newUserToken) => {
    setUserToken(newUserToken);

    //raha vao misy miditra ho authenticated dia ampidirina ny jwt decodé
    const decode = jwtDecode(newUserToken);
    setUser(decode);

    setIsAuthenticated(true);
    //maka ilay token voaforona
    localStorage.setItem("token", newUserToken);
  }, []);

  const logout = () => {
    setUserToken(null); //foanana ny isAuthenticated rehefa logount
    setAuthLoading(true);
    // asiana timeout kely
    setTimeout(() => {
      // hanaovana remove cache rehetra hoan'ny data/query teo aloha ao amin'ny query
      queryClient.removeQueries(["user", "songs"]);
      localStorage.removeItem("token");
      setIsAuthenticated(false);
      setAuthLoading(false);
      setUser(null);
      navigate("/login", { replace: true });
    }, 2000);
  };

  return (
    <AuthContext
      value={{ userToken, login, logout, authLoading, isAuthenticated, user }}
    >
      {children}
    </AuthContext>
  );
};

AuthProvider.propTypes = {
  children: PropTypes.node.isRequired,
};

export default AuthProvider;
