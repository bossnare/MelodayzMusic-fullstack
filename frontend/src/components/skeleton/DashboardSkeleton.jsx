import { SongCardSkeleton } from "./SongCardSkeleton";


export const DashboardSkeleton = () => {
  return (
    <section className="pb-70 pt-7 container animate-pulse">
      {/* Title */}
      <h1 className="text-lg sm:text-2xl md:text-xl lg:text-4xl font-bold pb-2 ml-2 bg-gray-300 h-6 w-40 rounded"></h1>

      {/* Discover Scrollable Items */}
      <div className="w-full overflow-x-auto md:overflow-x-hidden bg-gray-50 h-40 lg:h-50 flex-nowrap mb-10 p-2 flex items-center gap-4">
        {[...Array(4)].map((_, index) => (
          <div
            key={index}
            className="h-full min-w-[calc(100%/2-4px)] lg:min-w-[calc(100%/3-6px)] bg-gray-200 rounded-lg"
          ></div>
        ))}
      </div>

      {/* Grid Skeleton */}
      <div className="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 border-t-gray-200 border-t pt-2">
        {[...Array(8)].map((_, index) => (
          <SongCardSkeleton key={index} />
        ))}
      </div>
    </section>
  );
};