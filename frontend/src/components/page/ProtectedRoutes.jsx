import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "./services/contextApi/useContext";
import { Loader } from "../motion/Loader";

export const ProtectedRoutes = () => {
  const { userToken, authLoading } = useAuth();
  if (authLoading) {
    return <Loader />
  }
  //raha tsy authenticated dia redirect @ login
  return userToken ? <Outlet /> : <Navigate to="/login" replace />;
};
