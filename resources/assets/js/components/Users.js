import React, { useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom';
import MaterialTable from "material-table";
import { useSnackbar } from 'notistack';
import { makeStyles } from '@material-ui/core/styles';
import CircularProgress from '@material-ui/core/CircularProgress';
import withReactContent from 'sweetalert2-react-content';
import Swal from 'sweetalert2';
const MySwal = withReactContent(Swal);

const useStyles = makeStyles(() => ({
    div: {
        width: '100px',
        height: '100px',
        position: 'absolute',
        top: 0,
        bottom: 0,
        left: 0,
        right: 0,
        margin: 'auto'
    },
    container: {
        marginTop: '100px'
      },
}));

export default function Users() {
    const [users, setUsers] = useState([]);
    const history = useHistory();
    const classes = useStyles();
    const { enqueueSnackbar } = useSnackbar();

    useEffect(() => {
        axios.get('/api/admin/users')
        .then(e => setUsers(e && e.data.users))
        .catch(err => history.push('/'))
    }, [])

    const deleteUser = (oldData) => {
        MySwal.fire({
            title: 'Etes-vous sûr de vouloir supprimer cet utilisateur ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33', 
            confirmButtonText: 'Oui !',
          }).then((result) => {
              if(result.isConfirmed){
                axios.delete(`/api/admin/users/${oldData.id}`)
                .then(e => {
                    let usersArray = [...users];
                    const index = usersArray.indexOf(oldData);
                    usersArray.splice(index, 1);
                    setUsers(usersArray);
                    enqueueSnackbar('Utilisateur supprimé', { variant: 'success' });
                })
                .catch(() => enqueueSnackbar('Une erreur s\'est produite ', { variant: 'error' }))
              }
          })
    }

    const updateUser = async (newData, oldData) => {
                await axios.patch(`/api/admin/users/${oldData.id}`, newData)
                .then(updated => {
                    let usersArray = [...users];
                    const index = usersArray.indexOf(oldData);
                    usersArray[index] = updated.data;
                    setUsers(usersArray);
                    enqueueSnackbar('Utilisateur modifié', { variant: 'success' });
                })
                .catch(() => enqueueSnackbar('Une erreur s\'est produite ', { variant: 'error' }))
    }

    const addUser = async (newData) => {
        const {name, email, password, role} = newData;
        if (!name || !email || !password || !role) {
            return enqueueSnackbar('Veuillez remplir tous les champs', { variant: 'warning' });
        }
        await axios.post(`/api/admin/users`, newData)
        .then(updated => {
            let usersArray = [...users];
            usersArray.push(newData)
            setUsers(usersArray);
            enqueueSnackbar('Utilisateur ajouté', { variant: 'success' });
        })
        .catch(() => enqueueSnackbar('Une erreur s\'est produite ', { variant: 'error' }))
}
    return (
        !users.length ? <div className={classes.div}>
            <CircularProgress />
        </div> : 
        <div className={classes.container}>
            <MaterialTable
                title="Liste des utilisateurs"
                columns={[
                    { title: 'Name', field: 'name' },
                    { title: 'Email', field: 'email' },
                    { title: 'Mot de passe', field: 'password' },
                    { title: 'Solde', field: 'solde' },
                    { title: 'Rôle', field: 'role', lookup: { admin: 'Admin', user: 'Utilisateur' },
                },
                ]}
                data={users}
                editable={{
                    onRowAdd: newData => addUser(newData),
                    onRowUpdate: (newData, oldData) => updateUser(newData, oldData),
                    onRowDelete: oldData => deleteUser(oldData)
                }}
                localization={{ body: { editRow: { deleteText: 'Valider la suppression ?' } } }}
            />
        </div>
    );
}