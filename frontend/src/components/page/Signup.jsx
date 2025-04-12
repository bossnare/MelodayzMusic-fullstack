import { useNavigate } from "react-router-dom";
import { MusicNotesPlus } from "@phosphor-icons/react";
import { useState } from "react";
import { Button } from "../motion/motionButton";
import { Roller } from "../motion/Roller";
import SignUpForm from "../userForm/SignUpForm";
import { clsx } from "clsx";

export const Signup = () => {
  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();

  const handleClick = (e) => {
    e.preventDefault();
    setIsLoading(true);
    setTimeout(() => {
      navigate("/login");
    }, 4000);
  };
  //comp
  return (
    <div
      className={`md:flex justify-center space-y-4 md:gap-8 h-[100vh] bg-gray-100 items-center w-full *:md:w-[calc(80%/2-4px)] *:min-h-80 *:p-4 *:rounded-xl *:gap-2 *:flex *:flex-col`}
    >
      <div
        className={clsx(
          "bg-white/60 shadow-sm prose !py-10",
          isLoading && "animate-pulse"
        )}
      >
        {isLoading && <Roller />}
        <h2 className="text-gray-600 mt-1">
          On commence l&apos;aventure ? Inscrivez-vous
        </h2>
        <p className="mb-0">Déja un compte ? </p>{" "}
        <Button
          value={"Connectez-vous."}
          classname={`${
            isLoading && "pointer-events-none"
          } text-blue-500 hover:text-blue-400 underline w-1/4 text-left mt-0`}
          eventHandler={handleClick}
          disabled={isLoading}
        />
        <h1 className=" font-black text-blue-500 flex items-center mt-auto gap-1.5 text-sm md:text-2xl">
          <MusicNotesPlus className="text-3xl" /> <span>MELODAYZMusic</span>
        </h1>
      </div>
      <SignUpForm classname={"justify-center -mt-4"} />
    </div>
  );
};
