import { useEffect, useState } from "react";
import { Formik, Form, Field, ErrorMessage } from "formik";
import * as Yup from "yup";
import axios from "axios";
import { useAuth } from "../page/services/contextApi/useContext";
import { useNavigate } from "react-router-dom";
import PropTypes from "prop-types";
import { Loader } from "../motion/Loader";
import { Button } from "../motion/motionButton";
import { Eye, EyeSlash } from "@phosphor-icons/react";

const validationSchema = Yup.object({
  email: Yup.string()
    .email("Invalid emails formatVeuillez entrer un email valide.")
    .required("Email requis"),
  password: Yup.string()
    .min(6, "Le mot de passe doit contenir au moins 6 caractères.")
    .required("Mot de passe requis"),
});

export const LoginForm = ({ classname }) => {
  const [serverError, setServerError] = useState("");
  const [loading, setLoading] = useState(false);
  const { userToken, login, authLoading } = useAuth();
  const navigate = useNavigate();
  const [isView, setIsView] = useState(false);

  useEffect(() => {
    if (userToken) {
      navigate("/dashboard", { replace: true });
    }
  }, [login, userToken, navigate]);

  if (userToken && authLoading) {
    return <Loader />;
  }

  const handleLogin = async (values, { setSubmitting }) => {
    try {
      setLoading(true);
      setServerError("");
      const response = await axios.post(
        "http://localhost:8000/api/login",
        values,
        {
          headers: { "Content-Type": "application/json" },
        }
      );

      if (response.data.token) {
        login(response.data.token);
      }
    } catch (err) {
      if (err.response.data) {
        console.error(err.response.data);
        setServerError("Identifiants invalides. Veuillez vérifier votre nom d'utilisateur ou mot de passe.");
      } else {
        setServerError("Une problème est survenu. Merci de réessayer plus tard.")
        console.log("Une problème est survenu. Merci de réessayer plus tard.");
      }
    } finally {
      setLoading(false);
      setSubmitting(false);
    }
  };

  return (
    <>
      <Formik
        initialValues={{ emails: "", passwords: "" }}
        validationSchema={validationSchema}
        onSubmit={handleLogin}
      >
        {({ isSubmitting, touched, errors }) => {
          const hasErrors = !!errors.emails || !!errors.passwords;

          return (
            <Form className={classname} noValidate>
              <label htmlFor="emails" className="signup-label !mt-0">
                Email
              </label>

              <Field
                type="email"
                id="emails"
                name="email"
                autoCorrect="off"
                placeholder='Email...'
                spellCheck="false"
                className={`input-text custom-input active:bg-gray-200 rounded-lg py-4 ${
                  touched.emails && errors.emails
                    ? "border-red-500 text-red-400"
                    : ""
                }`}
              />

              <label htmlFor="passwords" className="signup-label">
                Mot de passe
              </label>
              <div className=" rounded-lg flex input-text p-0 custom-input overflow-hidden justify-end *:items-center *:flex *:justify-center">
                <Field
                  type={isView ? "text" : "password"}
                  id="passwords"
                  name="password"
                  placeholder="Mot de passe..."
                  className={`border-hidden px-4 placeholder:text-slate-400 active:bg-gray-200 ring-0 w-full py-4 ${
                    touched.passwords && errors.passwords
                      ? "border-red-500 text-red-400"
                      : ""
                  }`}
                />
                <div
                  onClick={() => {
                    setIsView(!isView)
                  }}
                  role="password-view"
                  className="shrink px-4 text-2xl items-center text-gray-400 hover:text-gray-500"
                >
                  <Button type={"button"} value={!isView ? <EyeSlash weight="bold" /> : <Eye weight="bold" />} />
                </div>
              </div>
              <ErrorMessage
                name="passwords"
                component="div"
                className="text-red-600"
              />

              {/* Submit Button */}
              <button
                className={`btn p-2 min-h-12 rounded-full font-bold ${"from-[#8E44AD] hover:opacity-80 bg-gradient-to-br transition-opacity duration-300 to-[#00BFFF] hover:bg-violet-500 text-white"}`}
                type="submit"
                disabled={isSubmitting || loading || hasErrors}
              >
                {loading ? (
                  <span className="border-4 mx-auto block h-4 rounded-full border-b-transparent w-4 border-gray-400 animate-spin"></span>
                ) : (
                  "Se Connecter"
                )}
              </button>

              {/* Server Errors */}
              {serverError && <p className="text-red-500">{serverError}</p>}
            </Form>
          );
        }}
      </Formik>
    </>
  );
};

LoginForm.propTypes = {
  classname: PropTypes.node.isRequired,
};
