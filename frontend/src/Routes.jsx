import { Routes, Route, Navigate } from "react-router-dom";
import { Dashboard } from "./components/page/Dashboard";
import { NotFound } from "./components/page/NotFound";
import UserProfile from "./components/page/bigpages/UserProfile";
import { Signup } from "./components/page/Signup";
import { ProtectedRoutes } from "./components/page/ProtectedRoutes";
import { DashboardLayout } from "./components/page/DashboardLayout";
import { AdminDashboard } from "./components/page/admin/AdminDashboard";
import { AdminRoute } from "./components/page/admin/AdminRoute";
import { Unauthorized } from "./components/page/Unauthorized";
import { SongPage } from "./components/page/Userapi/SongPage";
import { Login } from "./components/userForm/Login";
import { Favorite } from "./components/page/Userapi/Favoris";
import { Parameter } from "./components/page/Userapi/Parameter";



const RoutesComponent = () => {
  return (
    <Routes>
      <Route path="/" element={<Navigate to="/dashboard" replace />} />
      <Route path="/login" element={<Login />} />
      <Route path="/register" element={<Signup />} />
      <Route element={<ProtectedRoutes />}>
        <Route path="/dashboard/*" element={<DashboardLayout />}>
          <Route index element={<Dashboard />} />
          <Route path="favoris" element={<Favorite />} />
          <Route path="profile" element={<UserProfile />} >
            <Route index element={<SongPage />} />
          </Route>
          <Route path="parameter" element={<Parameter />} />
          <Route path="song/:id" element={<SongPage />} />
        </Route>
      </Route>
      <Route path="/admin" element={<AdminRoute />}>
        <Route index element={<AdminDashboard />} />
      </Route>
      <Route path="*" element={<NotFound />} />
      <Route path="/unauthorized" element={<Unauthorized />} />
    </Routes>
  );
};
export default RoutesComponent;
