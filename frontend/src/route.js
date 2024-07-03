import Main from "./components/Main/TimeLiveScroll.vue"
import User from "./components/User/User.vue";

export const routes = [
    { path: '/', component: Main },
    { path: '/user', component: User }
];

export function findComponentByPath(path, routes) {
    return routes.find(route => route.path === path);
}