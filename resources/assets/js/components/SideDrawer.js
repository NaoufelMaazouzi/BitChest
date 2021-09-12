import React from 'react';
import clsx from 'clsx';
import { makeStyles, useTheme } from '@material-ui/core/styles';
import { Link } from 'react-router-dom';
import axios from 'axios'
import { useHistory } from 'react-router-dom';
import AccountBalanceWalletIcon from '@material-ui/icons/AccountBalanceWallet';
import SupervisorAccountIcon from '@material-ui/icons/SupervisorAccount';
import BarChartIcon from '@material-ui/icons/BarChart';
import PersonIcon from '@material-ui/icons/Person';
import ExitToAppIcon from '@material-ui/icons/ExitToApp';
import ChevronLeftIcon from '@material-ui/icons/ChevronLeft';
import ChevronRightIcon from '@material-ui/icons/ChevronRight';
import MenuIcon from '@material-ui/icons/Menu';
import {
  Drawer,
  AppBar,
  Toolbar,
  List,
  CssBaseline,
  Typography,
  Divider,
  IconButton,
  ListItem,
  ListItemIcon,
  ListItemText,
  Button
} from '@material-ui/core';

import BitchestLogo from '../../images/bitchest_logo.png';

const drawerWidth = 200;

const useStyles = makeStyles((theme) => ({
  root: {
    display: 'flex',
  },
  appBar: {
    zIndex: theme.zIndex.drawer + 1,
    transition: theme.transitions.create(['width', 'margin'], {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.leavingScreen,
    }),
  },
  appBarShift: {
    marginLeft: drawerWidth,
    width: `calc(100% - ${drawerWidth}px)`,
    transition: theme.transitions.create(['width', 'margin'], {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.enteringScreen,
    }),
  },
  menuButton: {
    marginRight: theme.spacing(2),
  },
  hide: {
    display: 'none',
  },
  drawer: {
    width: drawerWidth,
    flexShrink: 0,
    whiteSpace: 'nowrap',
  },
  drawerOpen: {
    width: drawerWidth,
    transition: theme.transitions.create('width', {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.enteringScreen,
    }),
  },
  drawerClose: {
    transition: theme.transitions.create('width', {
      easing: theme.transitions.easing.sharp,
      duration: theme.transitions.duration.leavingScreen,
    }),
    overflowX: 'hidden',
    width: theme.spacing(7) + 1,
    [theme.breakpoints.up('sm')]: {
      width: theme.spacing(9) + 1,
    },
  },
  toolbar: {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'flex-end',
    padding: theme.spacing(0, 1),
    // necessary for content to be below app bar
    ...theme.mixins.toolbar,
  },
  content: {
    flexGrow: 1,
    padding: theme.spacing(3),
  },
  logo: {
    height: '3rem',
    width: '10rem',
  },
  divLogo: {
    width: '100%',
    display: 'flex',
    justifyContent: 'flex-end',
    alignItems: 'center'
  }
}));

const listItem = [
  {title: 'Portefeuille', icon: () => <AccountBalanceWalletIcon/>, link: '/', permission: ['user', 'admin']},
  {title: 'Cryptos', icon: () => <BarChartIcon/>, link: '/cryptoList', permission: ['user', 'admin']},
  {title: 'Données perso', icon: () => <PersonIcon/>, link: '/personalData', permission: ['user', 'admin']},
  {title: 'Utilisateurs', icon: () => <SupervisorAccountIcon/>, link: '/admin/users', permission: ['admin']},
];

export default function SideDrawerComponent(props) {
  const classes = useStyles();
  const theme = useTheme();
  const [open, setOpen] = React.useState(false);
  const { userLoggedin } = props;
  const history = useHistory();

  const handleDrawerOpen = () => {
    setOpen(true);
  };

  const logout = () => {
    axios.post('/logout')
            .then(() => history.go(0))
            .catch(e => !console.log('err', e) );
  }

  const handleDrawerClose = () => {
    setOpen(false);
  };

  return (
    <div className={classes.root}>
      <CssBaseline />
      <AppBar
        position="fixed"
        className={clsx(classes.appBar, {
          [classes.appBarShift]: open,
        })}
      >
        <Toolbar>
        {props.userLoggedin.user && <IconButton
            color="inherit"
            aria-label="open drawer"
            onClick={handleDrawerOpen}
            edge="start"
            className={clsx(classes.menuButton, {
              [classes.hide]: open,
            })}
          >
            <MenuIcon />
          </IconButton>}
          <div className={classes.divLogo}>
            <img src={BitchestLogo} className={classes.logo} />
            {userLoggedin.user ? 
                <Button onClick={logout} color="inherit">Logout</Button>
            :
            <Link to="/login">
              <Button color="inherit">Login</Button>
            </Link> }
          </div>
        </Toolbar>
      </AppBar>
      {props.userLoggedin.user && <Drawer
        variant="permanent"
        className={clsx(classes.drawer, {
          [classes.drawerOpen]: open,
          [classes.drawerClose]: !open,
        })}
        classes={{
          paper: clsx({
            [classes.drawerOpen]: open,
            [classes.drawerClose]: !open,
          }),
        }}
      >
        <div className={classes.toolbar}>
          <IconButton onClick={handleDrawerClose}>
            {theme.direction === 'rtl' ? <ChevronRightIcon /> : <ChevronLeftIcon />}
          </IconButton>
        </div>
        <Divider />
        <List>
          {listItem.map(item => ( 
              <Link to={item.link} style={{ color: '#000' }} >
                <ListItem button key={item.title}>
                {item.permission.includes(props.userLoggedin.user) ?
                <React.Fragment>
                  <ListItemIcon>{item.icon()}</ListItemIcon>
                  <ListItemText primary={item.title} />
                </React.Fragment> : ''
                }
                </ListItem>
              </Link>
          ))}
        </List>
        <Divider />
            <ListItem onClick={logout} button key="deconnexion">
                <ListItemIcon><ExitToAppIcon /></ListItemIcon>
                <ListItemText primary="deconnexion" />
            </ListItem>
      </Drawer>}
    </div>
  );
}