import { NavLink } from "react-router-dom";
import { useAuth } from "../services/contextApi/useContext";
import {
  User,
  CaretDown,
  UserCircleGear,
  Power,
  Gear,
  ShieldCheck,
  CaretUp
} from "@phosphor-icons/react";
import { Button } from "../../motion/motionButton";
import { useState } from "react";

export const Aside = () => {
  const { user, logout } = useAuth();
  const admin = user.roles.includes("ROLE_ADMIN");
  const [isDown, setIsDown] = useState(true)

  return (
    <aside
      id="side-bar"
      className="hidden md:block bg-white overflow-y-auto top-16 h-[calc(100vh-4rem)] z-10 fixed w-1/6"
    >
      <div className="relative h-full select-none">
        <div className="*:py-3 *:px-1 *:min-w-10 *:text-left *:rounded-md flex flex-col *:flex *:gap-2 *:items-center *:font-semibold *:hover:bg-gray-100 *:transition-all *:duration-200 *:ease-in gap-1 pt-2 pb-10 px-6">
          <div className="flex flex-col *:px-2 *:flex !p-0 *:py-3 *:w-full overflow-hidden !gap-0">
            <div className="gap-2 cursor-pointer" onClick={() => {
              setIsDown(!isDown)
            }}>
              <UserCircleGear className="text-2xl" /> <span className="hidden text-gray-800 lg:block"> Profil</span>
              <Button
                classname={"ml-auto text-xl"}
                value={isDown ? <CaretUp weight="bold" /> : <CaretDown weight="bold" />}
              />
            </div>
            <ul className={`flex-col bg-gray-50 gap-4 *:hover:!bg-gray-100 *:text-black/70 **:text-black/70 *:!bg-transparent **:bg-transparent *:flex **:flex **:items-center *:items-center *:gap-2 **:gap-2 !px-2 !py-0 border border-gray-200 rounded-lg rounded-t-none overflow-hidden transition-opacity ease-in-out duration-500 will-change-auto  ${ isDown ? 'min-h-30 !pt-2 !pb-2 opacity-100' : 'opacity-0 h-0 border-0'}`}>
              <NavLink>
                <figure className="size-5 shrink-0 overflow-hidden rounded-full">
                  <img className="h-full w-full object-cover" src={user?.profile?.pictureUrl || user?.default} alt="profile" loading="lazy" />
                </figure>
                <span className="!hidden lg:!block truncate text-nowrap">{user.name}</span>
              </NavLink>
              <NavLink>
                <Gear className="text-lg" />
                <span className="!hidden lg:!block">Paramètres</span>
              </NavLink>
              {admin ? <NavLink to="/admin"> <ShieldCheck/> <span>Accès Admin</span> </NavLink> : null}
              <Button
              eventHandler={logout}
                classname={"bg-transparent"}
                value={
                  <NavLink>
                    <Power className="text-lg" /> <span>Déconnexion</span>
                  </NavLink>
                }
              />
              
            </ul>
          </div>
          {/* <NavLink
            className={({ isActive }) => (isActive ? "active" : "undefined")}
            to="/dashboard/profile"
          >
            <User className="text-2xl" />
            <span className="select-none">Profil</span>
          </NavLink> */}
        
        </div>
        {/* <a className={` sticky container right-0 flex justify-center bg-gray-100/50 items-center bottom-0 h-14 w-full font-bold`}>
          <button
            onClick={logout}
            className=" py-2 rounded-md w-48"
          >
            Deconnexion
          </button>
        </a> */}
      </div>
    </aside>
  );
};
