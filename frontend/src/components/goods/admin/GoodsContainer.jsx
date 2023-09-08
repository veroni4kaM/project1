import React, {useEffect, useState} from "react";
import axios from "axios";
import {responseStatus} from "../../../utils/consts";
import {Helmet} from "react-helmet-async";
import {Breadcrumbs, Link, Pagination, Typography} from "@mui/material";
import {NavLink, useNavigate, useSearchParams} from "react-router-dom";
import GoodsList from "./GoodsList";
import {checkFilterItem, fetchFilterData} from "../../../utils/fetchFilterData";
import userAuthenticationConfig from "../../../utils/userAuthenticationConfig";
import GoodsFilter from "./GoodsFilter";
import GoodsCreate from "./GoodsCreate";

const GoodsContainer = () => {

    const navigate = useNavigate();
    const [searchParams] = useSearchParams();

    const [goods, setGoods] = useState(null);

    const [paginationInfo, setPaginationInfo] = useState({
        totalItems: null,
        totalPageCount: null,
        itemsPerPage: 5
    });

    const [filterData, setFilterData] = useState({
        "page": checkFilterItem(searchParams, "page", 1, true),
        "name": checkFilterItem(searchParams, "name", null),
        "priceMin": checkFilterItem(searchParams, "priceMin", null),
        "priceMax": checkFilterItem(searchParams, "priceMax", null),
        "description": checkFilterItem(searchParams, "description", null),
        "creationDate": checkFilterItem(searchParams, "creationDate", null),
    });
    const createProduct = (productData) => {
        axios.post("/api/products", productData, userAuthenticationConfig())
            .then((response) => {
                if (response.status === responseStatus.HTTP_CREATED && response.data["hydra:member"]) {
                    fetchProducts();
                }
            })
            .catch((error) => {
                console.error("Помилка при створенні продукту", error);
            });
    };
    const fetchProducts = () => {

        let filterUrl = fetchFilterData(filterData);
        navigate(filterUrl);

        axios.get("/api/products" + filterUrl + "&itemsPerPage=" + paginationInfo.itemsPerPage, userAuthenticationConfig()).then(response => {
            if (response.status === responseStatus.HTTP_OK && response.data["hydra:member"]) {
                setGoods(response.data["hydra:member"]);
                setPaginationInfo({
                    ...paginationInfo,
                    totalItems: response.data["hydra:totalItems"],
                    totalPageCount: Math.ceil(response.data["hydra:totalItems"] / paginationInfo.itemsPerPage)
                });
            }
        }).catch(error => {
            console.log("error");
        });
    };

    const onChangePage = (event, page) => {
        setFilterData({...filterData, page: page});
    };

    useEffect(() => {
        fetchProducts();
    }, [filterData]);

    console.log(paginationInfo);

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
            <Typography component="h3" mt={1} color="inherit">
                Створення продукту
            </Typography>
            <GoodsCreate createProduct={createProduct}/>
            <Typography component="h3" mt={1} color="inherit">
                Фільтрація
            </Typography>
            <GoodsFilter
                filterData={filterData}
                setFilterData={setFilterData}
            />
            <Typography component="h3" mt={1} color="inherit">
                Продукти
            </Typography>
            <GoodsList
                goods={goods}
            />
            {paginationInfo.totalPageCount>=0 &&
                <Pagination
                    count={paginationInfo.totalPageCount}
                    shape="rounded"
                    page={filterData.page}
                    onChange={(event, page) => onChangePage(event, page)}
                />
            }
        </>
    );

};

export default GoodsContainer;