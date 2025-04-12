import { useQuery } from "@tanstack/react-query";
import { useEffect } from "react";
import { SongCard } from "./SongCard";
import apis from "../services/api/apis";

export const Favorite = () => {

  const fetchNewsFeed = async () => {
    const response = await apis.get("/api/favorites", { timeout: 10000 });
    console.log(response.data);
    const { member } = response.data;
    return member;
  };

  const options = {
    queryKey: ["favorites"],
    queryFn: fetchNewsFeed,
    staleTime: 5000,
    cacheTime: 100,
    retry: 3,
    retryDelay: 1000,
    refectInterval: 50000,
    refetchOnReconnect: true,
    refetchOnMount: true,
    refetchOnWindowsFocus: true,
  };
  const navTarget = "favorites";

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

  const { data: favorites, isPending, error, isError, refetch } = useQuery(options);

  if (isError) {
    return <button onClick={refetch}></button>
  }

  if (isPending) {
    return <span>Loading...</span>
  }

  if (error) {
    return <p>error {error.message}</p>;
  }

  return (
    <section className="pb-70 pt-7 container ">
      <h1 className="text-lg sm:text-2xl md:text-xl lg:text-4xl font-bold pb-2">Discover</h1>
      <div className="w-full overflow-x-auto md:overflow-x-hidden bg-gray-50 h-40 lg:h-50 flex-nowrap mb-10 p-2 flex items-center *:h-full *:min-w-[calc(100%/2-4px)] *:lg:min-w-[calc(100%/3-6px)] *:bg-gray-100 gap-4 *:rounded-lg">
        <div></div>
        <div>
          {/* <Roller /> */}
        </div>
        <div></div>
        <div></div>
        
        
      </div>
      <div className="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 border-t-gray-200 border-t pt-2">
        {favorites.map((favorite) => (
          <SongCard key={favorite?.id} song={favorite} />
        ))}
      </div>
    </section>
  );
};