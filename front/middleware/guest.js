export default ({ $auth, redirect}) => {
    if ($auth.loggedIn) {
        return redirect('/')
    }
}