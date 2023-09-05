import { lazy } from "react";
import routes from "./routes";

const GoodsPage = lazy(() => import("../pages/goods/GoodsPage"));

const userRoutes = [
  {
    path: "/panel/goods",
    element: <GoodsPage />
  },
];

const userRoutesConcat = userRoutes.concat(routes);

export default userRoutesConcat;