import * as React from 'react';
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import CssBaseline from '@mui/material/CssBaseline';
import TextField from '@mui/material/TextField';
import FormControlLabel from '@mui/material/FormControlLabel';
import Checkbox from '@mui/material/Checkbox';
import Link from '@mui/material/Link';
import Grid from '@mui/material/Grid';
import Box from '@mui/material/Box';
import LockOutlinedIcon from '@mui/icons-material/LockOutlined';
import Typography from '@mui/material/Typography';
import Container from '@mui/material/Container';
import { createTheme, ThemeProvider } from '@mui/material/styles';
import { Notyf } from "notyf";
import 'notyf/notyf.min.css';
import axios from 'axios';
const defaultTheme = createTheme();

export default function Register() {
  const handleSubmit = (event) => {
    event.preventDefault();
    const data = new FormData(event.currentTarget);
    var email= data.get('email');
    var password= data.get('password');
    if(email==''){
        notyf.open({
            type: "error",
            message: "Email is required",
        });
    }else if(password==''){
        notyf.open({
            type: "error",
            message: "Password is required",
        });
    }else{
        axios.post('/checkLogin',{
            email:email,
            password:password
        }).then((res)=>{
            if(res.data.check==true){
                notyf.open({
                    type: "success",
                    message: "Login successfully",
                });
            }else if(res.data.check==false){
                if(res.data.msg){
                    notyf.open({
                        type: "error",
                        message: res.data.msg,
                    });
                }
            }
        })
    }
  };
  const notyf = new Notyf({
    duration: 1000,
    position: {
        x: "right",
        y: "top",
    },
    types: [
        {
            type: "warning",
            background: "orange",
            icon: {
                className: "material-icons",
                tagName: "i",
                text: "warning",
            },
        },
        {
            type: "error",
            background: "indianred",
            duration: 2000,
            dismissible: true,
        },
    ],
});
  return (
    <ThemeProvider theme={defaultTheme}>
      <Container component="main" maxWidth="xs">
        <CssBaseline />
        <Box
          sx={{
            marginTop: 8,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
          }}
        >
          <Avatar sx={{ m: 1, bgcolor: 'secondary.main' }}>
            <LockOutlinedIcon />
          </Avatar>
          <Typography component="h1" variant="h5">
           LOGIN
          </Typography>
          <Box component="form" noValidate onSubmit={handleSubmit} sx={{ mt: 3 }}>
            <Grid container spacing={2}>
              <Grid item xs={12}>
                <TextField
                  required
                  fullWidth
                  id="email"
                  label="Email Address"
                  name="email"
                  autoComplete="email"
                />
              </Grid>
              <Grid item xs={12}>
                <TextField
                  required
                  fullWidth
                  name="password"
                  label="Password"
                  type="password"
                  id="password"
                  autoComplete="new-password"
                />
              </Grid>
            </Grid>
            <Button
              type="submit"
              fullWidth
              variant="contained"
              sx={{ mt: 3, mb: 2 }}
            >
              LOGIN
            </Button>
            <Grid container justifyContent="flex-end">
              <Grid item>
                <Link href="/register" variant="body2">
                  Register
                </Link>
              </Grid>
            </Grid>
          </Box>
        </Box>
      </Container>
    </ThemeProvider>
  );
}
