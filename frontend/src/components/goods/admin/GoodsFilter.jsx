import React, {useState} from "react";
import TextField from "@mui/material/TextField";
import Box from "@mui/material/Box";
import {Button, Typography} from "@mui/material";
import {DateTimePicker} from '@mui/x-date-pickers';

const GoodsFilter = ({filterData, setFilterData, onCreateProduct}) => {
    const [productFormData, setProductFormData] = useState({
        name: "",
        price: "",
        description: "",
        category: "",
    });

    const onChangeFilterData = (name, value) => {
        setFilterData({...filterData, [name]: value});
    };

    const onChangeProductData = (name, value) => {
        setProductFormData({...productFormData, [name]: value});
    };

    const categoryValueToDisplay = productFormData.category.replace('/api/categories/', '');

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
                    onChange={(event) => onChangeFilterData("name", event.target.value)}
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
                    onChange={(event) => onChangeFilterData("price[gte]", event.target.value)}
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
                    onChange={(event) => onChangeFilterData("price[lte]", event.target.value)}
                    sx={{width: 200}}
                    inputProps={{min: 0}}
                />
            </Box>
            <Box mb={2}>
                <DateTimePicker
                    name="createdAt[gte]"
                    label="CreatedAt-in"
                    value={filterData["createdAt[gte]"]}
                    onChange={(date) => onChangeFilterData("createdAt[gte]", date)}
                />
            </Box>
            <Box mb={2}>
                <DateTimePicker
                    name="createdAt[lte]"
                    label="CreatedAt-out"
                    value={filterData["createdAt[lte]"]}
                    onChange={(date) => onChangeFilterData("createdAt[lte]", date)}
                />
            </Box>
            <Typography variant="h6" component="h6" mb={1}>
                Create Product
            </Typography>
            <Box mb={2}>
                <TextField
                    id="name"
                    label="Name"
                    variant="outlined"
                    fullWidth
                    size="small"
                    name="name"
                    value={productFormData.name}
                    onChange={(event) => onChangeProductData("name", event.target.value)}
                    sx={{width: 200}}
                />
            </Box>
            <Box mb={2}>
                <TextField
                    id="price"
                    label="Price"
                    variant="outlined"
                    fullWidth
                    size="small"
                    type="number"
                    name="price"
                    value={productFormData.price}
                    onChange={(event) => onChangeProductData("price", event.target.value)}
                    sx={{width: 200}}
                    inputProps={{min: 0}}
                />
            </Box>
            <Box mb={2}>
                <TextField
                    id="description"
                    label="Description"
                    variant="outlined"
                    fullWidth
                    size="small"
                    name="description"
                    value={productFormData.description}
                    onChange={(event) => onChangeProductData("description", event.target.value)}
                    sx={{width: 200}}
                />
            </Box>
            <Box mb={2}>
                <TextField
                    id="category"
                    label="Category ID"
                    variant="outlined"
                    fullWidth
                    size="small"
                    type="text"
                    name="category"
                    value={categoryValueToDisplay} // Отображаем обработанное значение
                    onChange={(event) => onChangeProductData("category", `/api/categories/${event.target.value}`)}
                    sx={{width: 200}}
                />
            </Box>
            <Box mb={2}>
                <Button
                    variant="contained"
                    color="primary"
                    onClick={() => {
                        onCreateProduct(productFormData);
                        setProductFormData({
                            name: "",
                            price: "",
                            description: "",
                            category: "",
                        });
                    }}
                >
                    Create Product
                </Button>
            </Box>
        </Box>
    );
};

export default GoodsFilter;
