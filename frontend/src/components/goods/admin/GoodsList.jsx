import React from "react";
import GoodsItem from "./GoodsItem";
import Grid from "@mui/material/Grid";

const GoodsList = ({goods}) => {
    return (
        <Grid container spacing={2}>
            {goods &&
                goods.map((item, key) => (
                    <Grid item xs={12} sm={6} md={4} lg={3} key={key}>
                        <GoodsItem good={item}/>
                    </Grid>
                ))}
        </Grid>
    );
};
export default GoodsList;
