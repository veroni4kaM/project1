import React, {useState} from "react";
import {
    Button,
    FormControl,
    InputLabel,
    Input,
    TextareaAutosize,
    Grid,
} from "@mui/material";

const GoodsCreate = ({createProduct}) => {
    const [productData, setProductData] = useState({
        name: "",
        price: "",
        description: "",
        //creationDate: ""
    });

    const handleChange = (e) => {
        const { name,price,description,creationDate, value } = e.target;
        setProductData({
            ...productData,
            [name]: value,
            [price]:value,
            [description]:value,
           // [creationDate]:value
        });
    };
    const handleSubmit = (e) => {
        e.preventDefault();
        createProduct(productData);
        setProductData({
            name: "",
            price: "",
            description: "",
           // creationDate: ""
        });
    };

    return (
        <form onSubmit={handleSubmit}>
            <Grid container spacing={2}>
                <Grid item xs={12}>
                    <FormControl fullWidth>
                        <InputLabel htmlFor="name">Назва</InputLabel>
                        <Input
                            id="name"
                            name="name"
                            value={productData.name}
                            onChange={handleChange}
                            required
                        />
                    </FormControl>
                </Grid>
                <Grid item xs={12}>
                    <FormControl fullWidth>
                        <InputLabel htmlFor="price">Ціна</InputLabel>
                        <Input
                            id="price"
                            name="price"
                            type="number"
                            value={productData.price}
                            onChange={handleChange}
                            required
                        />
                    </FormControl>
                </Grid>
                <Grid item xs={12}>
                    <FormControl fullWidth>
                        <InputLabel htmlFor="description">Опис</InputLabel>
                        <TextareaAutosize
                            id="description"
                            name="description"
                            minRows={3}
                            value={productData.description}
                            onChange={handleChange}
                            required
                        />
                    </FormControl>
                </Grid>
                <Grid item xs={12}>
                    <Button type="submit" variant="contained" color="primary">
                        Створити продукт
                    </Button>
                </Grid>
            </Grid>
        </form>
    );
};

export default GoodsCreate;
