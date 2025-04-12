import { useContext, useEffect, useState } from "react";
import Select from "react-select"; // Ho an'ny genre
import apis from "../page/services/api/apis";
import { motion } from "motion/react";
import { UploadSimple, X } from "@phosphor-icons/react";
import { Button, Label } from "../motion/motionButton";
import { UploadContext } from "../page/services/contextApi/UploadContext";
import { useToast } from "../motion/Toast";

const SongUpload = () => {
  const { isShow, setIsShow } = useContext(UploadContext);
  const { showToast } = useToast();

  useEffect(() => {
    if (isShow) {
      document.body.classList.add("overflow-hidden");
    } else {
      document.body.classList.remove("overflow-hidden");
    }
  }, [isShow]);

  const [formData, setFormData] = useState({
    title: "",
    artist: "",
    genre: null,
    songFile: null,
    coverFile: null,
    coverImageUrl: null, // Mba hitahirizana ilay sary ho an'ny preview
    description: "", // Add description field
  });

  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const [type, setType] = useState(null);

  const genres = [
    { value: "rock", label: "Rock" },
    { value: "pop", label: "Pop" },
    { value: "hiphop", label: "Hip Hop" },
    { value: "jazz", label: "Jazz" },
    { value: "afrobeat", label: "Afrobeat" },
    { value: "funk", label: "Funk" },
    { value: "next", label: "Next" },
    { value: "afropop", label: "Afropop" },
  ];

  // Miasa amin'ny song file
  const handleSongFileChange = (e) => {
    setFormData({ ...formData, songFile: e.target.files[0] });
    if (e.target.files[0]) {
      setType(e.target.files[0].type);
    } else {
      setType(null);
    }
  };

  // Miasa amin'ny cover file (sary)
  const handleCoverFileChange = (e) => {
    const file = e.target.files[0];
    setFormData({ ...formData, coverFile: file });

    // Mampiasa FileReader hamaky ilay cover image
    const reader = new FileReader();
    reader.onloadend = () => {
      setFormData((prevState) => ({
        ...prevState,
        coverImageUrl: reader.result,
      }));
    };

    if (file) {
      reader.readAsDataURL(file);
    }
  };

  // Fanamarinana ireo saha (Validation)
  const validate = () => {
    const newErrors = {};

    if (!formData.title) newErrors.title = "Azafady, asio lohateny.";
    if (!formData.artist) newErrors.artist = "Azafady, asio mpanakanto.";
    if (!formData.songFile) newErrors.songFile = "Azafady, asiana rakikira.";
    if (!formData.genre) newErrors.genre = "Azafady, misafidiana genre.";
    if (!formData.description)
      newErrors.description = "Azafady, asio famaritana.";

    return newErrors;
  };

  // Mandefa form data amin'ny API
  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    // Validation
    const validationErrors = validate();

    if (Object.keys(validationErrors).length > 0) {
      setErrors(validationErrors);
      setLoading(false);
      return;
    }

    const form = new FormData();
    form.append("title", formData.title);
    form.append("artist", formData.artist);
    form.append("genre", formData.genre.value);
    form.append("file", formData.songFile);
    form.append("type", type);
    form.append("coverFile", formData.coverFile);
    form.append("description", formData.description); // Add description to FormData

    try {
      // Mandefa song sy cover amin'ny backend API
      const response = await apis.post("/api/songs/upload_song", form);

      if (response.data) {
        setIsShow(false)
        showToast("Vita ny upload", { bg: "bg-black", duration: 10000 });
      } else {
        alert("Misy olana tamin'ny upload.");
      }
    } catch (error) {
      alert("Nisy olana tamin'ny upload.");
      console.log(error);
    }

    setLoading(false);
  };

  return (
    <motion.div
      initial={{ opacity: 1, y: 0 }}
      animate={{ opacity: isShow ? 1 : 0, y: isShow ? 0 : "50rem" }}
      transition={{ duration: 0.4, ease: "easeInOut" }}
      className="w-full fixed z-20 max-h-dvh mx-auto p-6 bg-black/80 inset-0"
    >
      <form
        onSubmit={handleSubmit}
        className="max-w-6xl grid grid-cols-4 gap-4 p-4 rounded-lg bg-white/98 overflow-auto mx-auto"
      >
        <div className="col-span-full text-4xl pb-2 font-bold text-center relative  text-gray-800">
          <h1>Créer un contenu Streaming</h1>
          <Button eventHandler={() => {
            setIsShow(false)
          } } classname={'top-0 absolute right-0 p-2 bg-gray-200 rounded-full text-xl active:bg-gray-100'} value={ <X weight="bold" /> } />
        </div>
        <div className="col-span-2">
          {/* Description */}
          <div className="">
            <label
              htmlFor="description"
              className="block text-lg font-semibold text-gray-600"
            ></label>
            <textarea
              placeholder="Décrivez votre chanson..."
              id="description"
              value={formData.description}
              onChange={(e) =>
                setFormData({ ...formData, description: e.target.value })
              }
              className={`input-text flex rounded-lg min-h-15 overflow-hidden w-full ${
                errors.description ? "border-red-500" : "border-gray-300"
              }`}
            />
            {errors.description && (
              <p className="text-red-500 text-sm">{errors.description}</p>
            )}
          </div>

          {/* Song File */}
          <div className=" flex gap-2 w-full">
            <Label
              label={<UploadSimple weight="bold" />}
              classnames={"text-2xl "}
              htmlFor={"songFile"}
            />

            <input
              type="file"
              id="songFile"
              accept="audio/*"
              onChange={handleSongFileChange}
              className={`hidden w-full p-2 mt-2 border ${
                errors.songFile ? "border-red-500" : "border-gray-300"
              } rounded-md focus:ring-2 focus:ring-indigo-500`}
            />
            <span></span>
            {errors.songFile && (
              <p className="text-red-500 text-sm">{errors.songFile}</p>
            )}
          </div>

          {/* Title */}
          <div className="">
            <label
              htmlFor="title"
              className="block text-lg font-semibold text-gray-700"
            >
              Titre de morceau:
            </label>
            <input
              type="text"
              id="title"
              value={formData.title}
              onChange={(e) =>
                setFormData({ ...formData, title: e.target.value })
              }
              className={`w-full p-2 mt-2 border ${
                errors.title ? "border-red-500" : "border-gray-300"
              } rounded-md focus:ring-2 focus:ring-indigo-500`}
            />
            {errors.title && (
              <p className="text-red-500 text-sm">{errors.title}</p>
            )}
          </div>

          {/* Artist */}
          <div className="">
            <label
              htmlFor="artist"
              className="block text-lg font-semibold text-gray-700"
            >
              Artist:
            </label>
            <input
              type="text"
              id="artist"
              value={formData.artist}
              onChange={(e) =>
                setFormData({ ...formData, artist: e.target.value })
              }
              className={`w-full p-2 mt-2 border ${
                errors.artist ? "border-red-500" : "border-gray-300"
              } rounded-md focus:ring-2 focus:ring-indigo-500`}
            />
            {errors.artist && (
              <p className="text-red-500 text-sm">{errors.artist}</p>
            )}
          </div>

          {/* Genre */}
          <div className="">
            <label
              htmlFor="genre"
              className="block text-lg font-semibold text-gray-700"
            >
              Sélectionnez un genre:
            </label>
            <Select
              id="genre"
              options={genres}
              value={formData.genre}
              onChange={(selectedOption) =>
                setFormData({ ...formData, genre: selectedOption })
              }
              className={`${
                errors.genre ? "border-red-500" : "border-gray-300"
              } p-2 mt-2 rounded-md`}
            />
            {errors.genre && (
              <p className="text-red-500 text-sm">{errors.genre}</p>
            )}
          </div>
        </div>
        <div className="col-span-2">
          {/* Cover File */}
          <div className="">
            <label
              htmlFor="coverFile"
              className="block text-lg font-semibold text-gray-700"
            >
              Ajouter une couverture:
            </label>
            <input
              type="file"
              id="coverFile"
              accept="image/*"
              onChange={handleCoverFileChange}
              className={`w-full p-2 mt-2 border ${
                errors.coverFile ? "border-red-500" : "border-gray-300"
              } rounded-md focus:ring-2 focus:ring-indigo-500`}
            />
            {errors.coverFile && (
              <p className="text-red-500 text-sm">{errors.coverFile}</p>
            )}
          </div>

          {/* Preview Cover Image */}
          {formData.coverImageUrl && (
            <div className="mb-4">
              <label className="block text-lg font-semibold text-gray-700">
                Cover:
              </label>
              <div className="w-[80%] h-50">
                <img
                  src={formData.coverImageUrl}
                  alt="Cover Preview"
                  className=" mt-2 rounded-md object-cover h-full w-full"
                />
              </div>
            </div>
          )}
        </div>
        <div className="col-span-4 ">
          {/* Submit Button */}
          <button
            type="submit"
            disabled={loading}
            className={`w-full mt-4 py-2 text-white font-semibold rounded-md ${
              loading ? "bg-gray-500" : "cta"
            } focus:outline-none focus:ring-2`}
          >
            {loading ? "Chargement..." : "Soumettre le morceau"}
          </button>
        </div>
      </form>
    </motion.div>
  );
};

export default SongUpload;
