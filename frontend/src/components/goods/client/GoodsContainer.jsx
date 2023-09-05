import { Breadcrumbs, Link, Typography } from "@mui/material";
import React from "react";
import { Helmet } from "react-helmet-async";
import { NavLink } from "react-router-dom";

const GoodsContainer = () => {
  return (
    <>
      <Helmet>
        <title>
          Sign in
        </title>
      </Helmet>
      <Breadcrumbs aria-label="breadcrumb">
        <Link component={NavLink} underline="hover" color="inherit" to="/">
          Home
        </Link>
        <Typography color="text.primary">Goods</Typography>
      </Breadcrumbs>
      <Typography variant="h4" component="h1" mt={1}>
        Goods
      </Typography>
      <div className="page-style">
        <code>
          //TODO <br/><br />
        </code>
      </div>
    </>
  );
};

export default GoodsContainer;