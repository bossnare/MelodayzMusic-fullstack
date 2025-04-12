import { useEffect, useState } from "react";
import { Formik, Form, Field, ErrorMessage } from "formik";
import { motion } from "motion/react";
import * as Yup from "yup";
import axios from "axios";
import PropTypes from "prop-types";
import { CheckCircle, Eye, EyeSlash } from "@phosphor-icons/react";
import { Button } from "../motion/motionButton";
import { Link, useNavigate } from "react-router-dom";
import { Simple } from "../motion/Simple";

const validationSchema = Yup.object({
  username: Yup.string()
    .min(3, "Le nom d'utilisateur doit contenir au moins 6 caractères.")
    .required("Nom d'utilisateur requis."),
  email: Yup.string()
    .email("Veuillez entrer un email valide.")
    .required("Email requis."),
  password: Yup.string()
    .min(6, "Le mot de passe doit contenir au moins 6 caractères.")
    .required("Mot de passe requis."),
});

const SignUpForm = ({ classname }) => {
  const [errorEmail, setErrorEmail] = useState("");
  const [errorUsername, setErrorUsername] = useState("");
  const [serverError, setServerError] = useState("");
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");
  const [isView, setIsView] = useState(false);
  const [isCreate, setIsCreate] = useState(false);

  const [isLoading, setIsLoading] = useState(false);
  const navigate = useNavigate();

  const handleClick = (e) => {
    e.preventDefault();
    setIsLoading(true);
    setTimeout(() => {
      navigate("/login");
    }, 4000);
  };

  useEffect(() => {
    if (isCreate) {
      document.body.classList.add("overflow-hidden");
    } else {
      document.body.classList.remove("overflow-hidden");
    }
  }, [isCreate]);

  const handleSignUp = async (values, { setSubmitting }) => {
    try {
      setLoading(true);
      setServerError("");
      setMessage("");

      const response = await axios.post(
        "http://localhost:8000/api/users/register",
        values,
        {
          headers: { "Content-Type": "application/json" },
        }
      );
      setMessage(response.data.message);
      setErrorEmail("");
      setErrorUsername("");
      setIsCreate(true);
    } catch (err) {
      if (err.response?.data?.status === "emailError") {
        setErrorEmail("veuillez vous connecter.");
      } else if (err.response?.data?.status === "usernameError") {
        setErrorUsername(err.response.data.error);
        console.log(err.response.data.error);
      } else {
        setServerError(err.response?.data?.error || "Something went wrong");
        console.log(err.response?.data);
      }
    } finally {
      setLoading(false);
      setSubmitting(false);
    }
  };

  return (
    <div className="w-full">
      <Formik
        initialValues={{ username: "", email: "", password: "" }}
        validationSchema={validationSchema}
        onSubmit={handleSignUp}
      >
        {({ isSubmitting, touched, errors, handleChange }) => {
          const hasErrors =
            !!errors.username ||
            !!errors.email ||
            !!errors.password ||
            !!errorEmail ||
            !!errorUsername;

          return (
            <Form className={classname} noValidate>
              {/* Username Field */}
              <motion.div
                initial={{ x: 0 }}
                animate={{
                  x: errorUsername || errors.username ? [-5, 5, -5, 5, 0] : 0,
                }}
                transition={{ duration: 0.3, ease: "easeInOut" }}
              >
                <label htmlFor="username" className="signup-label">
                  Nom d&apos;utilisateur
                </label>
                <Field
                  type="text"
                  id="username"
                  name="username"
                  autoCorrect="off" // Manafana auto-correct
                  spellCheck="false" // Manafana spell-check underline
                  className={`input-text custom-input active:bg-gray-200 rounded-md py-3 !mb-2 ${
                    errorUsername || (touched.username && errors.username)
                      ? "border-red-500"
                      : ""
                  }`}
                />
                <ErrorMessage
                  name="username"
                  component="div"
                  className="text-red-600"
                />
                {errorUsername && (
                  <div className="text-red-600">{errorUsername}</div>
                )}
              </motion.div>

              {/* Email Field */}
              <motion.div
                initial={{ x: 0 }}
                animate={{
                  x: errors.email || errorEmail ? [-5, 5, -5, 5, 0] : 0,
                }}
                transition={{ duration: 0.3 }}
              >
                <label htmlFor="email" className="signup-label">
                  Email
                </label>
                <Field
                  type="email"
                  id="email"
                  name="email"
                  autoCorrect="off"
                  spellCheck="false"
                  className={`input-text custom-input active:bg-gray-200 rounded-md py-3 !mb-2 ${
                    !!errorEmail || (touched.email && errors.email)
                      ? "border-red-500 text-red-400"
                      : ""
                  }`}
                  onChange={(e) => {
                    handleChange(e);
                    setErrorEmail("");
                  }}
                />
                <ErrorMessage
                  name="email"
                  component="div"
                  className="text-red-600"
                />
                {errorEmail && (
                  <div className="text-red-600">
                    L&apos;email est déjà enregistré. Si vous avez un compte,
                    <Link to="/login" className="text-blue-500 underline block">
                      {errorEmail}
                    </Link>
                  </div>
                )}
              </motion.div>

              {/* Password Field */}
              <motion.div
                initial={{ x: 0 }}
                animate={{
                  x:
                    touched.password && errors.password ? [-5, 5, -5, 5, 0] : 0,
                }}
                transition={{ duration: 0.3 }}
              >
                <label htmlFor="password" className="signup-label">
                  Mot de passe
                </label>
                <div className=" rounded-md flex input-text p-0 custom-input overflow-hidden justify-end *:items-center *:flex *:justify-center !mb-2">
                  <Field
                    type={isView ? "text" : "password"}
                    id="password"
                    name="password"
                    className={`border-hidden px-4 placeholder:text-slate-400 active:bg-gray-200 ring-0 w-full py-3 ${
                      touched.password && errors.password
                        ? "border-red-500 text-red-400"
                        : ""
                    }`}
                  />
                  <div
                    onClick={() => {
                      setIsView(!isView);
                    }}
                    role="password-view"
                    className="shrink px-4 text-2xl items-center text-gray-400 hover:text-gray-500"
                  >
                    <Button
                      value={
                        !isView ? (
                          <EyeSlash weight="bold" />
                        ) : (
                          <Eye weight="bold" />
                        )
                      }
                    />
                  </div>
                </div>
                <ErrorMessage
                  name="password"
                  component="div"
                  className="text-red-600"
                />
              </motion.div>

              {/* Submit Button */}
              <button
                className={`btn p-2 w-full min-h-12 rounded-full font-bold ${
                  hasErrors
                    ? "bg-gray-400 cursor-not-allowed text-gray-300"
                    : "bg-blue-500 hover:bg-blue-600 text-white"
                }`}
                type="submit"
                disabled={isSubmitting || loading || hasErrors}
              >
                {loading ? (
                  <span className="border-4 mx-auto block h-4 rounded-full border-b-transparent w-4 border-gray-400 animate-spin"></span>
                ) : (
                  "S'inscrire"
                )}
              </button>

              {/* Server Errors */}
              {serverError && <p className="text-red-500">{serverError}</p>}
            </Form>
          );
        }}
      </Formik>
      <motion.div
        initial={{ opacity: 0, y: 0 }}
        animate={{ opacity: isCreate ? 1 : 0, y: isCreate ? 0 : "38rem" }}
        transition={{ duration: 0.4, ease: "easeInOut" }}
        className={` w-full fixed z-20 max-h-dvh mx-auto grid items-end bg-black/50 backdrop-blur-xs inset-0`}
      >
        <div className="bg-gray-50 py-6 max-w-full h-90 rounded-t-3xl prose mb-0 flex flex-col items-center relative">
          {isLoading && (
            <div className="w-full absolute top-0 left-0 h-6">
              <Simple />
            </div>
          )}
          <h1 className="text-gray-600 mx-auto text-center flex items-center gap-2">
            Inscription réussie!{" "}
            <span>
              <CheckCircle className="text-green-400" weight="bold" size={34} />
            </span>
          </h1>
          <p className="w-100 text-center text-gray-500">
            Félicitations {message && message}, votre compte a été créé avec
            succès. Vous pouvez maintenant vous connecter
          </p>
          <Button
            type={"button"}
            value={"Continuer"}
            classname={`${"px-4 py-2 mt-8 rounded-md from-[#8E44AD] hover:opacity-80 bg-gradient-to-br transition-opacity duration-300 to-[#00BFFF] hover:bg-violet-500 text-white"}`}
            eventHandler={handleClick}
          />
        </div>
      </motion.div>
    </div>
  );
};

export default SignUpForm;

SignUpForm.propTypes = {
  classname: PropTypes.node.isRequired,
};
