import { useQuery } from "@tanstack/react-query";
import apis from "../services/api/apis";
import { ProfileSkeleton } from "../../skeleton/ProfileSkeleton";
import { UserProfileOverlay } from "../overlay/UserProfileOverlay";
import { Button } from "../../motion/motionButton";
import { useEffect } from "react";
import { SongCard } from "../Userapi/SongCard";
import { MySong } from "../Userapi/MySong";

const fetchUserProfile = async () => {
  const response = await apis.get("/api/moi", { timeout: 10000 });
  console.log(response.data);
  return response.data;
};

const options = {
  queryKey: ["user"],
  queryFn: fetchUserProfile,
  staleTime: 5000,
  cacheTime: 100,
  retry: 3,
  retryDelay: 1000,
  refectInterval: 5000,
  refetchOnReconnect: false,
  refetchOnMount: false,
  refetchOnWindowsFocus: false,
};

const UserProfile = () => {
  const navTarget = "profile";

  useEffect(() => {
    const savedScroll = sessionStorage.getItem(`scroll-${navTarget}`);
    if (savedScroll) {
      window.scrollTo(0, parseInt(savedScroll));
      console.log("yaaa: ", savedScroll);
    }
  }, []);

  useEffect(() => {
    const handleScroll = () => {
      sessionStorage.setItem(`scroll-${navTarget}`, window.scrollY);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const { data: user, isPending, isError, refetch } = useQuery(options);

  if (isError) {
    return <UserProfileOverlay refetch={refetch} />;
  }

  if (isPending) {
    return <ProfileSkeleton />;
  }

  return (
    <section className=" container">
      <div className="bg-black/2 flex gap-6 items-center">
        <div className="size-20 shrink-0 overflow-hidden md:size-33 outline-offset-1 relative outline-gray-200 outline-3 cursor-pointer rounded-full">
          <img
            className="object-cover h-full w-full"
            src={
              user.activateProfilePictures
                ? user.activateProfilePictures.pictureUrl
                : user.defaultProfilePictures
            }
            alt="profilePicture"
            loading="lazy"
          />
        </div>

        <div className="prose ">
          <h1 className="text-black/75 mb-0 pb-0">{user.username}</h1>
          <p>{user.bio}</p>
        </div>
      </div>
      <div className="grid grid-cols-1 py-2 gap-2 *:py-5">
        <nav className="bg-gray-100">My Song</nav>
        <div className="bg-gray-200 h-50 grid grid-cols-3 gap-6">
          {
            user?.songs.map((song) => (
              <MySong key={song.id} song={song} />
            ))
          }
        </div>
      </div>
    </section>
  );
};

export default UserProfile;
