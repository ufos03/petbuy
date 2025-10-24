export function get_user()
{
    const user_token = localStorage.getItem("user");
    
    if (user_token == null)
        return -1;
    return user_token;
}

export function clean_board()
{
    jQuery("#posts-grid").empty();
}