import React from "react";
import GoodsItem from "./GoodsItem";
import {
    Table,
    TableBody,
    TableCell,
    TableContainer,
    TableHead,
    TableRow,
    Paper,
} from "@mui/material";

const GoodsList = ({goods}) => {

    return <>
        <TableContainer component={Paper}>
            <Table>
                <TableHead>
                    <TableRow>
                        <TableCell>Name</TableCell>
                        <TableCell>Price</TableCell>
                        <TableCell>Description</TableCell>
                        <TableCell>Creation Date</TableCell>
                    </TableRow>
                </TableHead>
                <TableBody>
                    {goods &&
                        goods.map((item, key) => (
                            <GoodsItem key={key} good={item} />
                        ))}
                </TableBody>
            </Table>
        </TableContainer>
    </>;

}
export default GoodsList;