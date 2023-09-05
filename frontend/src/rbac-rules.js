import { goods, } from "./rbac-consts";

const rules = {
  ROLE_ADMIN: {
    static: [
      goods.ADMIN
    ],
    dynamic: {}
  },
  ROLE_USER: {
    static: [
      goods.USER
    ],
    dynamic: {}
  }
};

export default rules;
