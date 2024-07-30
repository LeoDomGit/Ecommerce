import React, { useEffect, useState } from 'react'

function Account(data) {
    const [user,setUser]= useState(data.data);
    useEffect(()=>{
        console.log(user);
    },[])
  return (
    <>

    </>
  )
}

export default Account
