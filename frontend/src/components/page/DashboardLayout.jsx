import { useToast, ToastWrapper } from "../motion/Toast";
import SongUpload from "../userForm/UploadSong";
import { Aside } from "./Three/Aside";
import { Header } from "./Three/Header";
import { MaintContent } from "./Three/MainContent";
import { NavBottom } from "./Three/NavBottom";
import { Player } from "./Userapi/Player";


export const DashboardLayout = () => {
  const { toasts } = useToast();
  return (
    <div className="grid grid-cols-1 md:grid-cols-12 relative">
      <Aside />
      <div className="col-span-1 md:col-start-3 md:col-span-10 items-start grid grid-cols-12 min-h-screen">
        <Header />
        <MaintContent />
        <NavBottom />
      </div>
      {/* modal */}
      <Player />
      <SongUpload />
      <ToastWrapper toasts={toasts} />
    </div>
  );
};
