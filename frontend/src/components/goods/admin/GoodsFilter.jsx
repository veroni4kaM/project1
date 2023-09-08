import React from "react";
import {
    Box,
    TextField,
} from "@mui/material";

const GoodsFilter = ({filterData, setFilterData}) => {

    const onChangeFilterData = (event) => {
        event.preventDefault();

        let {name, priceMin, priceMax, value} = event.target;

        setFilterData({
            ...filterData,
            [name]: value,
            [priceMin]: name === "priceMin" ? value : filterData.priceMin,
            [priceMax]: name === "priceMax" ? value : filterData.priceMax
        });
    }
    return <>
        <TextField
            label="Назва"
            id="name"
            type="text"
            name="name"
            defaultValue={filterData.name ?? ""}
            onChange={onChangeFilterData}
        />
        <Box display="flex" alignItems="center">
            <TextField
                label="Мінімальна ціна"
                name="priceMin"
                type="number"
                value={filterData.priceMin || ""}
                onChange={onChangeFilterData}
            />
            <Box mx={1}>-</Box>
            <TextField
                label="Максимальна ціна"
                name="priceMax"
                type="number"
                value={filterData.priceMax || ""}
                onChange={onChangeFilterData}
            />
        </Box>
    </>;
}
export default GoodsFilter;