import { hasAdminAccess, hasRole } from "../utils/store";

export const privilegeGuard = (next, role) => {
    if (hasRole(role)) {
        next();
    } else {
        next('/');
    }
};

export const adminAccessGuard = (to, from, next) => {
    if (!hasAdminAccess()) {
        next('/panel/admin-access?to=' + encodeURIComponent(to.fullPath));
    } else {
        next();
    }
};
