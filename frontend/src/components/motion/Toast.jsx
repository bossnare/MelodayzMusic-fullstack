import { useState } from "react";
import { createPortal } from "react-dom";

export const useToast = () => {
  const [toasts, setToasts] = useState([]);

  const showToast = (message, options = {}) => {
    const id = Date.now();
    setToasts((prev) => [...prev, { id, message, ...options, visible: false }]);

    // 🚀 FIX: Mba tsy hiseho tampoka ilay voalohany
    setTimeout(() => {
      setToasts((prev) =>
        prev.map((toast) =>
          toast.id === id ? { ...toast, visible: true } : toast
        )
      );
    }, 10); 

    setTimeout(() => {
      setToasts((prev) =>
        prev.map((toast) =>
          toast.id === id ? { ...toast, visible: false } : toast
        )
      );
      setTimeout(() => {
        setToasts((prev) => prev.filter((toast) => toast.id !== id));
      }, 300);
    }, options.duration || 3000);
  };

  return { showToast, toasts };
};

export const ToastWrapper = ({ toasts }) => {
  return createPortal(
    <div className="sticky bottom-10 left-1/2 transform -translate-x-1/2 z-50 space-y-2">
      {toasts.map(({ id, message, bg = "bg-black/80", text = "text-white", visible }) => (
        <div
          key={id}
          className={`px-4 py-2 rounded-lg shadow-md text-sm transition-all duration-300 ${
            visible ? "opacity-100 scale-100" : "opacity-0 scale-95"
          } ${bg} ${text}`}
        >
          {message}
        </div>
      ))}
    </div>,
    document.body
  );
};