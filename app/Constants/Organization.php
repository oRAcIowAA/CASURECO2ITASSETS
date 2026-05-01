<?php

namespace App\Constants;

class Organization
{
    public const DEPT_DIVISIONS = [
        'OFFICE GENERAL MANAGER DEPARTMENT (OGM)' => 
        [
        ],
        'CORPORATE PLANNING DEPARTMENT (CORPLAN)' => 
        [
            0 => 'I.T. DIVISION',
            1 => 'CORPORATE PLANNING DIVISION',
        ],
        'INSTITUTIONAL SERVICE DEPARTMENT (ISD)' => 
        [
            0 => 'HUMAN RESOURCE DIVISION',
            1 => 'MANAGEMENT SERVICE DIVISION',
        ],
        'FINANCE DEPARTMENT (FIN DEPT)' => 
        [
            0 => 'ACCOUNTING DIVISION',
            1 => 'BUDGET & TREASURER DIVISION',
        ],
        'AUDIT DEPARTMENT (AUDIT DEPT)' => 
        [
            0 => 'FINANCIAL DIVISION',
            1 => 'TECHNICAL DIVISION',
        ],
        'ENGINEERING DEPARTMENT (ENGR DEPT)' => 
        [
            0 => 'SYSTEMS LOSS REDUCTION DIVISION',
            1 => 'PLANNING DIVISION',
            2 => 'CONSTRUCTION MANAGE & SERVICING DIVISION',
        ],
        'NORTH AREA OFFICE (NAO)' => 
        [
            0 => 'CANAMAN MAGARAO SUB-OFFICE (CMSO)',
            1 => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
            2 => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
            3 => 'NAGA AREA (COLLECTION OFFICE)',
        ],
        'SOUTH AREA OFFICE (SAO)' => 
        [
            0 => 'PILI SUB-OFFICE (PSO)',
            1 => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
        ],
    ];

    public const DEPARTMENTS = [
        0 => 'OFFICE GENERAL MANAGER DEPARTMENT (OGM)',
        1 => 'CORPORATE PLANNING DEPARTMENT (CORPLAN)',
        2 => 'INSTITUTIONAL SERVICE DEPARTMENT (ISD)',
        3 => 'FINANCE DEPARTMENT (FIN DEPT)',
        4 => 'AUDIT DEPARTMENT (AUDIT DEPT)',
        5 => 'ENGINEERING DEPARTMENT (ENGR DEPT)',
        6 => 'NORTH AREA OFFICE (NAO)',
        7 => 'SOUTH AREA OFFICE (SAO)',
    ];

    public const LOCATIONS = [
        0 => 'MAIN OFFICE (MAIN)',
        1 => 'NAGA AREA OFFICE (NAO)',
        2 => 'PILI SUB-OFFICE (PSO)',
        3 => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
        4 => 'CANAMAN MAGARAO SUB-OFFICE(CMSO)',
        5 => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
        6 => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
    ];

    public const DIVISIONS = [
        0 => 'I.T. DIVISION',
        1 => 'CORPORATE PLANNING DIVISION',
        2 => 'HUMAN RESOURCE DIVISION',
        3 => 'MANAGEMENT SERVICE DIVISION',
        4 => 'ACCOUNTING DIVISION',
        5 => 'BUDGET & TREASURER DIVISION',
        6 => 'FINANCIAL DIVISION',
        7 => 'TECHNICAL DIVISION',
        8 => 'SYSTEMS LOSS REDUCTION DIVISION',
        9 => 'PLANNING DIVISION',
        10 => 'CONSTRUCTION MANAGE & SERVICING DIVISION',
        11 => 'CANAMAN MAGARAO SUB-OFFICE (CMSO)',
        12 => 'CALABANGA BOMBON SUB-OFFICE (CBSO)',
        13 => 'TINAMBAC SIRUMA SUB-OFFICE (TSSO)',
        14 => 'NAGA AREA (COLLECTION OFFICE)',
        15 => 'PILI SUB-OFFICE (PSO)',
        16 => 'MILAOR MINALABAC SUB-OFFICE (MMSO)',
    ];
}
