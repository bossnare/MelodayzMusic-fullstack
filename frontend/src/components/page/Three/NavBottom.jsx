import { NavLink } from "react-router-dom";
import { Button } from "../../motion/motionButton";
import {
  Plus,
  MusicNote,
  Activity,
  House,
  SpinnerGap,
  UserCircle,
} from "@phosphor-icons/react";
import { useContext } from "react";
import { UploadContext } from "../services/contextApi/UploadContext";

export const NavBottom = () => {
  const { isShow, isLoading, handleButtonPlus } = useContext(UploadContext);
  const nav = [
    { id: "1", label: "Accueil", route: "/dashboard", icon: House },
    {
      id: "2",
      label: "Activité",
      route: "/dashboard/activity",
      icon: Activity,
    },
    { id: "3", label: "button" },
    {
      id: "4",
      label: "Favoris",
      route: "/dashboard/favoris",
      icon: MusicNote,
    },
    { id: "5", label: "Vous", route: "/dashboard/profile", icon: UserCircle },
  ];

  return (
    <nav className="fixed md:left-1/6 bottom-0 border-t-gray-200 border-t w-full md:w-5/6 text-sm md:text-base px-2 md:px-0 h-18 md:h-14 bg-gray-50 flex items-center justify-center *:flex *:flex-col gap-3 md:gap-5 lg:gap-2 md:py-4 *:py-1 *:flex-wrap *:justify-center *:items-center *:bg-amber-20 *:font-semibold *:w-22 md:*:w-40 *:min-h-14 *:max-h-14">
      {/* mampiasa end, inona? raha samy misy dashboard ilay route dia ilay active foana active fa tsy miaraka index */}

      {nav.map((tab) =>
        tab.label === "button" ? (
          <div
            key={tab.id}
            className="flex relative !w-30 md:!w-70 md:text-lg lg:text-xl text-white"
          >
            <Button
              value={
                isLoading ? (
                  <SpinnerGap
                    weight="bold"
                    className="animate-spin text-gray-300"
                  />
                ) : (
                  <>
                    <Plus
                      weight="bold"
                      className={`${
                        !isLoading && isShow ? "rotate-45" : "rotate-0"
                      } transition-transform size-6 opacity-85 duration-900 ease-in-out rotate-0`}
                    />
                    <span>{isShow ? 'Création' : 'Créer'}</span>
                  </>
                )
              }
              classname={
                "cta absolute shadow-md py-2 text-center !font-medium w-22 flex justify-center items-center gap-2 md:w-1/2 border-gray-200 border-1  rounded-lg"
              }
              eventHandler={handleButtonPlus}
            />
          </div>
        ) : (
          <NavLink
            className={({ isActive }) => (isActive ? "active" : "undefined")}
            to={tab.route}
            key={tab.id}
            end={tab.label === "Accueil"}
          >
            {({ isActive }) => {
              const Icon = tab.icon;
              return (
                <>
                  <Icon
                    weight={isActive ? "fill" : "regular"}
                    className="text-2xl hover:text-blue-500"
                  />

                  <span className={`select-none ${isActive ? 'text-blue-500' : 'text-gray-800'} `}>{tab.label}</span>
                </>
              );
            }}
          </NavLink>
        )
      )}
    </nav>
  );
};
