import { useAuth } from "../services/contextApi/useContext";

export const AdminDashboard = () => {
  const { user } = useAuth();
  return (
    <div className="prose">
      <h1 className="font-semibold"> Bienvenue <span >{user.name} UserId: {user.id} !</span></h1> 
    </div>
  );
};
