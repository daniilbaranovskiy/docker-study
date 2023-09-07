import React from "react";
import TextField from "@mui/material/TextField";
import Box from "@mui/material/Box";
import {Typography} from "@mui/material";
import {DatePicker} from '@mui/x-date-pickers';
import formatDate from "../../../utils/formatDate";

const GoodsFilter = ({filterData, setFilterData}) => {
    const onChangeFilterData = (name, value) => {
        setFilterData({...filterData, [name]: value});
    };

    return (
        <Box mb={2}>
            <Typography variant="h6" component="h6" mb={1}>
                Filter
            </Typography>
            <Box mb={2}>
                <TextField
                    id="name"
                    label="Name"
                    variant="outlined"
                    fullWidth
                    size="small"
                    name="name"
                    defaultValue={filterData.name ?? ""}
                    onChange={(event) => onChangeFilterData(event, "name", event.target.value)}
                    sx={{width: 200}}
                />
            </Box>
            <Box mb={2}>
                <TextField
                    id="minPrice"
                    label="Min Price"
                    variant="outlined"
                    fullWidth
                    size="small"
                    type="number"
                    name="price[gte]"
                    defaultValue={filterData["price[gte]"] ?? ""}
                    onChange={(event) => onChangeFilterData(event, "price[gte]", event.target.value)}
                    sx={{width: 200}}
                    inputProps={{min: 0}}
                />
            </Box>
            <Box mb={2}>
                <TextField
                    id="maxPrice"
                    label="Max Price"
                    variant="outlined"
                    fullWidth
                    size="small"
                    type="number"
                    name="price[lte]"
                    defaultValue={filterData["price[lte]"] ?? ""}
                    onChange={(event) => onChangeFilterData(event, "price[lte]", event.target.value)}
                    sx={{width: 200}}
                    inputProps={{min: 0}}
                />
            </Box>
            <Box mb={2}>
                <DatePicker
                    name="createdAt[after]"
                    label="CreatedAt-in"
                    value={filterData["createdAt[after]"]}
                    onChange={(value) => onChangeFilterData("createdAt[after]", formatDate(value))}
                />
            </Box>
            <Box mb={2}>
                <DatePicker
                    name="createdAt[before]"
                    label="CreatedAt-out"
                    value={filterData["createdAt[before]"]}
                    onChange={(value) => onChangeFilterData("createdAt[before]", formatDate(value))}
                />
            </Box>
        </Box>
    );
};

export default GoodsFilter;
