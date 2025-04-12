import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "../services/contextApi/useContext";
import { Loader } from "../../motion/Loader";

export const AdminRoute = () => {
  const { authLoading, userToken, user } = useAuth();

  if (authLoading) return <Loader />

  if (!userToken) return <Navigate to='/login' />

  console.log(user);
  console.log(user?.roles.includes('ROLE_ADMIN'))

  return userToken && user.roles.includes('ROLE_ADMIN') ? (
    <Outlet />
  ) : (
    <Navigate to="/unauthorized" />
  );


};
