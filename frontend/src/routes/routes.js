import { lazy } from "react";

const HomePage = lazy(() => import("../pages/home/HomePage"));
const LoginPage = lazy(() => import("../pages/login/LoginPage"));

const routes = [
  {
    path: "/",
    element: <HomePage />
  },
  {
    path: "/login",
    element: <LoginPage />
  }
];

export default routes;