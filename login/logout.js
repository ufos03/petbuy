import { call_logout_api } from "./api.js";

function clear_local_session() {
    localStorage.removeItem("user");
    location.href = 'https://petbuy-local.ns0.it:8080/'
}

function logout()
{
    call_logout_api(
        localStorage.getItem("user"),
        clear_local_session,
        clear_local_session // In case of error, clear the session anyway
    );
}

export function logout_handler() {
    
    const token = localStorage.getItem("user");
    if (token == null)
        return;

    logout();
}