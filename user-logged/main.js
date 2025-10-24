export function is_user_logged_in()
{
    if (jQuery("body").hasClass("logged-in") == true)
        return true;
    return false;
}

export function get_user()
{
    const user_token = localStorage.getItem("user");
    
    if (user_token == null)
        return -1;
    return user_token;
}