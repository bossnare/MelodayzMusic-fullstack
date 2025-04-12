import { ArrowCircleLeft, WarningCircle } from "@phosphor-icons/react";
import { Link } from "react-router-dom";
import { Button } from "../motion/motionButton";

export const NotFound = () => {
  return (
    <div className="mx-auto max-w-1/2 p-10 mt-12 relative bg-gray-100 overflow-hidden rounded-sm">
      <div className="absolute bg-red-50 top-0 left-0 h-full w-22 flex items-center justify-center text-4xl">
        <WarningCircle className="text-red-600" />
      </div>
      <div className="prose ml-16">
        <h1>Page non trouvée !</h1>
        <p>Oops, nous n`avons pas trouvé la page que vous cherchez.</p>
        <p>Vérifiez l`URL ou revenez à l`accueil.</p>
        <Button
          value={
            <Link to="/" className="flex gap-2 text-blue-700 items-center">
              <span>
                <ArrowCircleLeft size={25} />
              </span>{" "}
              Retour à l`accueil
            </Link>
          }
        />
      </div>
    </div>
  );
};
