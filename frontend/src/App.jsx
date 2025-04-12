import RoutesComponent from "./Routes";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import AuthProvider from "./components/page/services/contextApi/AuthContext";
import UploadProvider from "./components/page/services/contextApi/UploadContext";
import FavoriteProvider from "./components/page/services/contextApi/FavoriteContext";

const queryClient = new QueryClient();

const App = () => {
  return (
    <QueryClientProvider client={queryClient}>
      <AuthProvider>
        <UploadProvider>
          <FavoriteProvider>
            <RoutesComponent />
          </FavoriteProvider>
        </UploadProvider>
      </AuthProvider>
    </QueryClientProvider>
  );
};
export default App;
