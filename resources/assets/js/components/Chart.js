import React, {useEffect, useState} from 'react';
import { AreaChart, Area, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';
import CoinGecko from 'coingecko-api';
import { makeStyles } from '@material-ui/core/styles';
import { AppBar, Tabs, Tab, Typography } from '@material-ui/core';
const CoinGeckoClient = new CoinGecko();
let cryptoName = '';

const useStyles = makeStyles(() => ({
  container: {
      marginTop: '100px'
  },
}));


export default function Chart(props) {
  const classes = useStyles();
  const [date, setDate] = useState(1);
  const [prices, setPrices] = useState([]);
  const [minPrice, setMinPrice] = useState(0);
  const [maxPrice, setMaxPrice] = useState(0);


  useEffect(() => {
    cryptoName = props.match.params.name;
    requestApi();
  }, []);

  const handleChange = (event, newValue) => {
    setDate(newValue);
    requestApi(newValue);
  }

  const requestApi = async (days) => {
    let cryptoData = await CoinGeckoClient.coins.fetchMarketChart(cryptoName.toLowerCase(), {
      vs_currency: 'eur',
    days: days ? days : 1
  });
    const prices = cryptoData.data.prices.map((ele) => ({
            date: new Date(ele[0]).toLocaleString(),
            price: (ele[1]).toFixed(2),
          }));
    setPrices(prices);
    const parsedMinPrice = parseFloat(prices.reduce((prev, curr) => {
      return prev.price < curr.price ? prev : curr;
    }).price);
    const parsedMaxPrice = parseFloat(prices.reduce((prev, curr) => {
      return prev.price > curr.price ? prev : curr;
    }).price);
    setMinPrice((parsedMinPrice - (parsedMinPrice / 85)).toFixed(2));
    setMaxPrice((parsedMaxPrice + (parsedMaxPrice / 85)).toFixed(2));
  }

  return (
    <div className={classes.container}>
      <AppBar position="static" color="default">
                <Tabs
                  value={date}
                  onChange={handleChange}
                  indicatorColor="primary"
                  textColor="primary"
                  variant="fullWidth"
                  aria-label="action tabs example"
                >
                  <Tab label="1 Jour" value={1}/>
                  <Tab label="5 Jours" value={5}/>
                  <Tab label="10 Jours" value={10}/>
                  <Tab label="30 Jours" value={30}/>
                </Tabs>
              </AppBar>
              <br/>
              <br/>
              <Typography
                variant="h3"
                component="h2">
                {cryptoName.charAt(0).toUpperCase() + cryptoName.slice(1)}
              </Typography>
              <ResponsiveContainer width={1100} height={500}>
                <AreaChart
                  width={500}
                  height={400}
                  data={prices}
                  margin={{
                    top: 10,
                    right: 30,
                    left: 0,
                    bottom: 0,
                  }}
                >
                  <CartesianGrid strokeDasharray="3 3" />
                  <XAxis dataKey="date" minTickGap={25} />
                  <YAxis type="number" domain={[parseFloat(minPrice), parseFloat(maxPrice)]} dataKey={(v) => v.price}/>
                  <Tooltip />
                  <Area type="monotone" dataKey="price" stroke="#8884d8" fill="#8884d8" />
                </AreaChart>
              </ResponsiveContainer>
      </div>
  );
}