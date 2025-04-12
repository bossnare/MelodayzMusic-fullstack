import { formatDistanceToNow } from "date-fns";
import { fr } from "date-fns/locale";

const dateAgo = (date) => {
  const timeAgo = formatDistanceToNow(new Date(date), {
    addSuffix: true,
    locale: fr,
  });

  // mamadika ho fohy
  const shortTime = timeAgo
    .replace("minute", "min")
    .replace("minutes", "mn")
    .replace("heure", "h")
    .replace("heures", "h")
    .replace("jour", "j")
    .replace("jours", "j")
    .replace("il y a moins d’une min", "A l'instant")
    .replace("environ", "");;


  return shortTime;
};

export default dateAgo;
