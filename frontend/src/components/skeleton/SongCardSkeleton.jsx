
export const SongCardSkeleton = () => {
  return (
    <div className="col-span-1 hover:bg-gray-50 animate-pulse">
      <div className="flex-col sm:flex sm:flex-row sm:flex-wrap sm:items-start md:flex-nowrap md:items-stretch md:flex-col border-gray-900/80">
        {/* Image Skeleton */}
        <div className="sm:flex-1/3 shrink-0 cursor-pointer relative overflow-hidden rounded-t-md">
          <figure className="bg-gray-300 shrink h-40 sm:h-50 md:w-full w-full md:h-40 lg:h-40 xl:h-34" />
          
        </div>

        {/* Info Skeleton */}
        <div className="grow p-2 sm:flex-1/2 flex flex-wrap gap-2">
          {/* Profile Picture Skeleton */}
          <div className="size-10 md:size-8 shrink-0 rounded-full bg-gray-300 border-gray-200 border-2" />

          {/* Username Skeleton */}
          <span className="md:w-[calc(100%-50px)] shrink-0 w-[calc(100%-200px)] mt-2 inline-block bg-gray-300 h-4 rounded" />

          {/* Date Skeleton */}
          <span className="w-30 lg:w-full py-4 h-3 bg-gray-300 rounded" />

          {/* Description Skeleton */}
          <p className="w-full h-6 bg-gray-200 rounded"></p>
        </div>

        {/* Action Buttons Skeleton */}
        
      </div>
    </div>
  );
};