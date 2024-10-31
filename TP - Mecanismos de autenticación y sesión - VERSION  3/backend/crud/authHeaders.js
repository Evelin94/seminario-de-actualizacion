export function getAuthHeaders() {

    const sessionidusuario = localStorage.getItem('idusuario');
    const sessionusuario = localStorage.getItem('usuario');
    const sessionpassword = localStorage.getItem('contraseña');
    const sessiontoken = localStorage.getItem('token');
    const sessionMetodo = localStorage.getItem('selectedAuthMethod');
  
    // Define los headers según el método de autenticación
    let headerSelect = {};
    if (sessionMetodo === 'token') {
        headerSelect = {
            'Content-Type': 'application/json',
             'X-Token': sessiontoken            
        };

        
    } else if (sessionMetodo === 'userPass') {
        headerSelect = {
            'Content-Type': 'application/json',
            'X-Usuario': sessionusuario,
            'X-Password': sessionpassword            
        };

        
    }

    return headerSelect;
}