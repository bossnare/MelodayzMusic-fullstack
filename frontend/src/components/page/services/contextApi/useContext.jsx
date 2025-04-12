import { useContext } from "react"
import { AuthContext } from "./AuthContext"
import { AudioContext } from "./AudioContext"
AudioContext

//authitication
export const useAuth = () => {
    return useContext(AuthContext)
}



