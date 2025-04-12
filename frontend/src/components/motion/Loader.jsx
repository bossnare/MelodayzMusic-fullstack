import { SpinnerGap } from "@phosphor-icons/react";

export const Loader = () => {
    return(
        <div className="fixed inset-0 flex justify-center items-center">
            <SpinnerGap size={35} className="animate-spin" />
        </div>
        
    )
}