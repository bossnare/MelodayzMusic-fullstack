export const ProfileSkeleton = () => {
    return (
        <div className="container pt-8 ">
          <div className="w-30 animate-pulse relative h-30 mb-4 cursor-pointer overflow-clip rounded-full">
            <div className="bg-gray-200 absolute w-full h-full"></div>
          </div>
          <div className="prose animate-pulse">
            <h1 className="bg-gray-200 w-1/2 p-5"></h1>
            <p className="w-1/2 bg-gray-200 p-5"></p>
          </div>
        </div>
      );
}