import {Route, Routes, useLocation, useNavigate} from "react-router-dom";
import {createContext, Suspense, useEffect, useMemo, useState} from "react";
import {HelmetProvider} from "react-helmet-async";
import {CircularProgress} from "@mui/material";
import nprogress from "nprogress";
import NotFoundPage from "./pages/notFound/NotFoundPage";
import routes from "./routes/routes";
import userRoutesConcat from "./routes/userRoutes";
import getUserInfo from "./utils/getUserInfo";
import eventBus from "./utils/eventBus";
import "nprogress/nprogress.css";
import "./assets/css/main.css";
import {LocalizationProvider} from '@mui/x-date-pickers';
import {AdapterDayjs} from '@mui/x-date-pickers/AdapterDayjs'
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc'; // Подключаем плагин для работы с UTC
import timezone from 'dayjs/plugin/timezone'; // Подключаем плагин для работы с часовыми поясами

export const AppContext = createContext({});

dayjs.extend(utc);
dayjs.extend(timezone);
function App() {

    dayjs.tz.setDefault('Europe/Kiev');

    const [authenticated, setAuthenticated] = useState(localStorage.getItem("token"));
    const location = useLocation();
    const navigate = useNavigate();

    const authRouteRender = () => {
        if (!authenticated) {

            return (
                routes.map((route, index) => (
                    <Route key={index} path={route.path} element={route.element}/>
                ))
            );
        } else {
            return (
                userRoutesConcat.map((route, index) => (
                    <Route key={index} path={route.path} element={route.element}/>
                ))
            );
        }
    };

    const handleOnIdle = () => {
        eventBus.on("logout", (data) => {

            localStorage.removeItem("clientId");
            localStorage.removeItem("token");

            setAuthenticated(false);
            navigate("/");
        });
    };

    useMemo(() => {
        nprogress.start();
    }, [location]);

    useEffect(() => {
        nprogress.done();
    }, [location]);

    useEffect(() => {
        handleOnIdle();
    }, []);



    return (
        <LocalizationProvider dateAdapter={AdapterDayjs}>

            <AppContext.Provider
                value={{
                    authenticated,
                    setAuthenticated,
                    user: getUserInfo()
                }}
            >
                <HelmetProvider>
                    <div className="container">
                        <Suspense fallback={<CircularProgress/>}>
                            <Routes>
                                {authRouteRender()}
                                <Route path="*" element={<NotFoundPage/>}/>
                            </Routes>
                        </Suspense>
                    </div>
                </HelmetProvider>
            </AppContext.Provider>
        </LocalizationProvider>

    );
}

export default App;
