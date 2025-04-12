import { Outlet } from "react-router-dom";

export const MaintContent = () => {
  return (
    <main className="col-span-12 pt-18 z-0 min-h-screen ">
      <Outlet />
    </main>
  );
};
