import {getExpirationDate, isExpired} from "./checkExpiredToken";
import eventBus from "./eventBus";

const userAuthenticationConfig = (jsonld = true, multipart = false) => {

    let type = jsonld ? "application/json+ld" : "application/json";
    let contentType = multipart ? "multipart/form-data" : type;

    if (localStorage.getItem("token") !== null) {
        if (isExpired(getExpirationDate(localStorage.getItem("token")))) {
            eventBus.dispatch("logout", {expired: true});
            return;
        }

        return {
            headers: {
                "Content-Type": contentType,
                "Authorization": "Bearer " + localStorage.getItem("token"),
                "Accept": type
            }
        };
    }

    return {
        headers: {
            "Content-Type": contentType,
            "Accept": type
        }
    };
};

export default userAuthenticationConfig;