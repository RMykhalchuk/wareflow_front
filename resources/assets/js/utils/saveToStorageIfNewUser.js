export function saveToStorageIfNewUser(isNewUser, email, password) {
    if (isNewUser === 1) {
        localStorage.setItem('email', email);
        localStorage.setItem('password', password);
    }
}
