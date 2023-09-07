import React from "react";
import Paper from "@mui/material/Paper";
import Typography from "@mui/material/Typography";
import formatDate from "../../../utils/formatDate";

const GoodsItem = ({good}) => {
    const createdAt = formatDate(good.createdAt)
    return (
        <Paper elevation={3} style={{padding: "16px", marginBottom: "16px"}}>
            <Typography variant="h6">{good.name}</Typography>
            <Typography variant="body1">Price: {good.price}</Typography>
            <Typography variant="body2">Description: {good.description}</Typography>
            <Typography variant="body2">Created At: {createdAt}</Typography>
        </Paper>
    );
};
export default GoodsItem;
