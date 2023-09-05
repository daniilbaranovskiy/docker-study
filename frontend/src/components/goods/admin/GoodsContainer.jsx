import React, { useEffect, useState } from "react";
import axios from "axios";
import { responseStatus } from "../../../utils/consts";
import { Helmet } from "react-helmet-async";
import { Breadcrumbs, Link, Typography } from "@mui/material";
import { NavLink } from "react-router-dom";

const GoodsContainer = () => {

  const [goods, setGoods] = useState(null);

  const fetchProducts = () => {
    axios.get("/api/products", {
      "headers": {
        "Authorization": "Bearer " + localStorage.getItem("token"),
        "Content-type": "application/json+ld",
        "Accept": "application/json+ld"
      }
    }).then(response => {
      if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
        setGoods(response.data["hydra:member"]);
      }
    }).catch(error => {
      console.log("error");
    });
  };

  useEffect(() => {
    fetchProducts();
  }, []);

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
        {goods && goods.map((item, key) => {
          return <div key={key}>
            <p>{item.name}</p>
          </div>;
        })}
      </div>
    </>
  );

};

export default GoodsContainer;