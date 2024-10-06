import React from "react";
import { createRoot } from "react-dom/client";
import "../css/style.css";
import { ChakraProvider } from '@chakra-ui/react'
import Example from "./components/League";

const root = createRoot(document.getElementById("root"));
root.render(
    <ChakraProvider>
    <Example />
    </ChakraProvider>
);
