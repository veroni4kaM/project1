import React from "react";
import {
    TableCell,
    TableRow,
} from "@mui/material";

const GoodsItem = ({good}) => {
    const creationDate = good.creationDate
        ? new Date(good.creationDate * 1000)
        : null;

    return (
        <TableRow>
            <TableCell>{good.name}</TableCell>
            <TableCell>{good.price || "Немає ціни"}</TableCell>
            <TableCell>{good.description || "Немає опису"}</TableCell>
            <TableCell>
                {creationDate ? creationDate.toDateString() : "Немає дати"}
            </TableCell>
        </TableRow>
    );
};

export default GoodsItem;
