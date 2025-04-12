import { useState } from "react";
// import jwtDecode from "jwt-decode";

export const UploadProfilePicture = () => {
  const [file, setFile] = useState(null);
  const [name, setName] = useState("");
  const [type, setType] = useState('')
  const token = localStorage.getItem("token");

  const handleChange = (e) => {
    const fileSelected = e.target.files[0];
    if (fileSelected) {
      setFile(fileSelected);
      setName(fileSelected.name);
      setType(fileSelected.type)
      console.log("File selected:", fileSelected);
      console.log(token)
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (!file) {
      console.log("No file selected");
      return;
    }

    const formData = new FormData();
    formData.append("file", file); 
    formData.append("pictureType", type); 

    console.log("FormData:", formData.get("file"));

    try {
      const response = await fetch("http://localhost:8000/api/profilePictures/upload_profile_picture", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${token}`,
        },
        body: formData,
      });

      if (!response.ok) {
        console.log("Error:", response.status, response.statusText);
      }

      const data = await response.json();
      console.log("Server Response:", data);
    } catch (err) {
      console.error("Fetch Error:", err);
    }
  };

  return (
    <form onSubmit={handleSubmit} className=" p-2 *:border space-y-2 prose">
      <label
        htmlFor="fileUpload"
        className="rounded-full text-white pt-1.5 cursor-pointer active:bg-blue-500 font-bold h-10 w-10 block text-center bg-blue-600"
      >
        +
      </label>
      <p className="!border-0">{name}</p>
      <input
        className="hidden p-2"
        id="fileUpload"
        type="file"
        name="file"
        accept="image/*"
        onChange={handleChange}
      />
      <button
        title="submit"
        type="submit"
        className="bg-blue-500 text-white focus:bg-blue-700 px-6 py-2 block rounded-sm"
      >
        Publier
      </button>
    </form>
  );
};
