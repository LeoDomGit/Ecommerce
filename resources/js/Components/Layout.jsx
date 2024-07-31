/* eslint-disable */
import React from 'react'
import { Sidebar, Menu, MenuItem, useProSidebar, SubMenu } from "react-pro-sidebar";
import MenuOutlinedIcon from "@mui/icons-material/MenuOutlined";
import CategoryIcon from '@mui/icons-material/Category';
import BrandingWatermarkIcon from '@mui/icons-material/BrandingWatermark';
import LogoutIcon from '@mui/icons-material/Logout';
import "../../css/app.css";
function Layout({ children }) {
  const { collapseSidebar } = useProSidebar();
  return (
    <>
      <div className='row w-100'>
      </div>
      <div style={({ height: "90vh" }, { display: "flex" })}>
        <Sidebar style={{ minHeight: "90vh" }}>
          <Menu>
            <MenuItem
              icon={<MenuOutlinedIcon />}
              onClick={() => {
                collapseSidebar();
              }}
              style={{ textAlign: "center" }}
            >
              {" "}
              <h4>Admin</h4>
            </MenuItem>
            <SubMenu label="Tài khoản" icon={<BrandingWatermarkIcon />}>
            <a href={'/admin/users'}> <MenuItem icon={<CategoryIcon />}>Tài khoản</MenuItem></a>
            </SubMenu>
            <SubMenu label="Bài viết" icon={<BrandingWatermarkIcon />}>
            <a href={'/admin/collection'}> <MenuItem icon={<CategoryIcon />}>Loại bài viết</MenuItem></a>
            <a href={'/admin/posts'}> <MenuItem icon={<CategoryIcon />}>Bài viết</MenuItem></a>
            </SubMenu>
           <a href={'/logout'}> <MenuItem icon={<LogoutIcon />}>Log out</MenuItem></a>
          </Menu>
        </Sidebar>
        <main className='p-4 w-100'>
          {children}
        </main>
      </div>

    </>
  )
}

export default Layout
